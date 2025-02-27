import { Component, OnInit } from '@angular/core';
import { DatePipe } from '@angular/common';
import {
	FormGroup,
	FormBuilder,
	FormControl,
	Validators,
	FormArray
} from '@angular/forms';
import { Router, ActivatedRoute } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { TranslateService } from '@ngx-translate/core';

import { InvoiceService } from '../../../../../core/services/invoice.service';
import { ItemService } from '../../../../../core/services/item.service';
import { ClientService } from '../../../../../core/services/client.service';
import { TaxService } from '../../../../../core/services/tax.service';
import { ProjectService } from '../../../../../core/services/project.service';
import { AuthenticationService } from '../../../../../core/services/authentication.service';

import { datepickerConfig } from '../../../../../core/helpers/admin.helper';

@Component({
	selector: 'app-invoice-edit',
	templateUrl: './invoice-edit.component.html',
	styleUrls: ['./invoice-edit.component.scss']
})

export class InvoiceEditComponent implements OnInit {
	editInvoiceForm: FormGroup;
	selectedItem: number;
	loginUser: any;
	invoice: any;
	projects: any;
	invoiceSettings: any;
	clients = [];
	items = [];
	itemsArray = [];
	taxes = [];
	itemTaxes = [];
	taxesNameValues = [];
	total_discount = 0.00;
	isFormSubmitted = false;
	isFormLoaded = false;
	invoices = {
		sub_total: 0,
		total_taxes: 0,
		discount: 0.00,
		adjustment: 0,
		total: 0
	}
	datepickerConfig = datepickerConfig;

	constructor(
		public translate: TranslateService,
		public datepipe: DatePipe,
		private router: Router,
		private route: ActivatedRoute,
		private formBuilder: FormBuilder,
		private toastr: ToastrService,
		private invoiceService: InvoiceService,
		private clientService: ClientService,
		private itemService: ItemService,
		private taxService: TaxService,
		private projectService: ProjectService,
		private authenticationService: AuthenticationService
	) {
		this.authenticationService.loginUser.subscribe(x => this.loginUser = x);
		this.datepickerConfig.dateInputFormat = this.loginUser.settings.date_format;
		this.route.paramMap.subscribe(params => {
			this.getById(params.get('id'));
		});
	}

	ngOnInit() {
		this.getProjects();
		this.getTaxes();
	}

	loadForm() {
		this.editInvoiceForm = this.formBuilder.group({
			id: [this.invoice.id],
			project_id: [Number(this.invoice.project_id)],
			client_id: [Number(this.invoice.client_id), Validators.required],
			invoice_date: [new Date(this.invoice.invoice_date), Validators.required],
			due_date: [new Date(this.invoice.due_date), Validators.required],
			reference: [this.invoice.reference],
			invoice_header_information_text: [this.invoice.invoice_header_information_text],
			status: [this.invoice.status],
			discount_type: [this.invoice.discount_type],
			invoice_number: [this.invoice.invoice_number],
			selectedItem: [null],
			discount: [this.invoice.discount],
			adjustment: [this.invoice.adjustment],
			recurrence: [this.invoice.recurrence],
			recurrence_pattern: [this.invoice.recurrence_pattern],
			recurrence_occurrences: [this.invoice.recurrence_occurrences],
			item: this.formBuilder.group({
				item_name: [null],
				item_description: [null],
				quantity: [null],
				unit_price: [null],
				item_unit: [null],
				taxes: [null],
				amount: [0]
			}),
			items: this.invoice.items
		});

		// --
		// Render selected items
		this.setSelectedItems(this.invoice.items);

		this.isFormLoaded = true;
	}

	get invoiceControl() { return this.editInvoiceForm.controls; }

	get itemControl() { return this.editInvoiceForm.get('item'); }

	getProjects() {
		this.projectService.getAll()
			.subscribe(data => {
				this.projects = data;
			});
	}

	getById(invoiceId) {
		this.invoiceService.getById(invoiceId)
			.subscribe(data => {
				this.invoice = data;
				this.getClients();
			});
	}

	getClients() {
		this.clientService.getAll()
			.subscribe(
				data => {
					this.clients = data;
					this.getItems();
				});
	}

	getItems() {
		this.itemService.getAll()
			.subscribe(
				data => {
					this.items = data;
					this.loadForm();
				});
	}

	getTaxes() {
		this.taxService.getAll().subscribe(
			data => {
				this.taxes = data;

				for (let iRow in this.taxes) {
					if (this.taxesNameValues[this.taxes[iRow].id] == undefined) {
						this.taxesNameValues[this.taxes[iRow].id] = [];
					}

					this.taxesNameValues[this.taxes[iRow].id] = this.taxes[iRow];
				}
			});
	}

	addDays(date: Date, days: number): Date {
		return new Date(date.setDate(date.getDate() + days))
	}

	projectChange(event: any) {
		if (this.editInvoiceForm.value.project_id == undefined || this.editInvoiceForm.value.project_id == null || this.editInvoiceForm.value.project_id == '') {
			this.editInvoiceForm.patchValue({ client_id: null });
			return;
		}

		this.editInvoiceForm.patchValue({ client_id: event.client_id });
	}

	setSelectedItems(items) {
		for (let iRow in items) {
			let taxes = [];
			for (let jRow in items[iRow].taxes) {
				taxes.push(items[iRow].taxes[jRow].id)
			}

			items[iRow].taxes = taxes;
			this.itemsArray.push(items[iRow]);
		}

		this.invoices.discount = this.invoice.discount;
		if (this.invoices.adjustment == 0.00) {
			this.invoices.adjustment = 0;
		}
		else {
			this.invoices.adjustment = this.invoice.adjustment;
		}
		// console.log('ad0', this.invoices.adjustment);
		this.getItemTaxes();
	}

	changeItem(event) {
		let taxes = [];
		for (let iRow in event.taxes) {
			taxes.push(event.taxes[iRow].id)
		}

		this.itemControl.patchValue({ item_name: event.name });
		this.itemControl.patchValue({ item_description: event.description });
		this.itemControl.patchValue({ quantity: 1 });
		this.itemControl.patchValue({ unit_price: event.price });
		this.itemControl.patchValue({ item_unit: event.unit });
		this.itemControl.patchValue({ taxes: taxes });
	}

	resetChildForm() {
		this.itemControl.patchValue({ item_name: null });
		this.itemControl.patchValue({ item_description: null });
		this.itemControl.patchValue({ quantity: null });
		this.itemControl.patchValue({ unit_price: null });
		this.itemControl.patchValue({ item_unit: null });
		this.itemControl.patchValue({ taxes: [] });
	}

	changeDiscountType(event) {
		if (event.id == 'not_discount') {
			this.invoices.discount = 0;
		}

		this.getItemTaxes();
	}

	changeDiscountAdjustment(event, type) {
		if (type == 'discount') {
			if (this.editInvoiceForm.value.discount_type == "not_discount") {
				this.toastr.error(this.translate.instant('invoices.create.error_messages.message7'), this.translate.instant('invoices.title'));
				return;
			}
			this.invoices.discount = parseFloat(event.target.value);
		} else {
			this.invoices.adjustment = parseFloat(event.target.value);
			// console.log('ad1', this.invoices.adjustment);
		}

		if (isNaN(this.invoices.discount)) {
			this.invoices.discount = 0;
		}

		if (isNaN(this.invoices.adjustment)) {
			this.invoices.adjustment = 0;
		}

		this.getItemTaxes();
	}

	addItem(event) {
		event.preventDefault();
		let item = this.editInvoiceForm.value.item;

		if (item.item_name == null || item.quantity == null || item.unit_price == null) {
			this.toastr.error(this.translate.instant('invoices.create.error_messages.message6'), this.translate.instant('invoices.title'));
			return false;
		}

		this.itemsArray.push(item);
		this.resetChildForm();
		this.invoices.discount = this.editInvoiceForm.value.discount;
		this.invoices.adjustment = this.editInvoiceForm.value.adjustment;
		// console.log('Ad2', this.invoices.adjustment);
		this.getItemTaxes();
	}

	saveItemDetail(index, name, value) {
		this.itemsArray[index][name] = value;
		this.getItemTaxes();
	}

	deleteItem(index) {
		this.itemsArray.splice(index, 1);
		this.getItemTaxes();
	}

	getItemTaxes() {
		this.itemTaxes = [];
		this.invoices.sub_total = 0;
		this.invoices.total_taxes = 0;
		this.invoices.total = 0;
		this.total_discount = 0.00;
		let totalItemAmount = 0;

		// --
		// Count item taxes
		for (let iRow in this.itemsArray) {
			totalItemAmount = this.itemsArray[iRow].quantity * this.itemsArray[iRow].unit_price;

			for (let jRow in this.itemsArray[iRow].taxes) {
				let totalTaxes = 0;
				if (this.itemTaxes[this.itemsArray[iRow].taxes[jRow]] == undefined) {
					this.itemTaxes[this.itemsArray[iRow].taxes[jRow]] = 0;
				}

				totalTaxes = (totalItemAmount * this.taxesNameValues[this.itemsArray[iRow].taxes[jRow]].tax_rate) / 100;
				this.invoices.total_taxes += totalTaxes;

				this.itemTaxes[this.itemsArray[iRow].taxes[jRow]] = this.itemTaxes[this.itemsArray[iRow].taxes[jRow]] + totalTaxes;

			}

			// --
			// Count sub total
			this.invoices.sub_total += totalItemAmount;
		}

		// Log Subtotal and Taxes
		// console.log('Sub Total:', this.invoices.sub_total);
		// console.log('Total Taxes:', this.invoices.total_taxes);

		// --
		// Count total
		if (this.editInvoiceForm.value.discount_type == "percent") {
			this.total_discount = ((this.invoices.sub_total + this.invoices.total_taxes) * this.invoices.discount) / 100;
		} else {
			this.total_discount = this.invoices.discount;
		}

		// Log Discount Type and Value
		// console.log('Discount Type:', this.editInvoiceForm.value.discount_type);
		// console.log('Discount Value:', this.invoices.discount);
		// console.log('Total Discount:', this.total_discount);
		// console.log('Adjustment:', this.invoices.adjustment);

		this.invoices.total = (this.invoices.sub_total + this.invoices.total_taxes - this.total_discount) + this.invoices.adjustment;

		// Log Adjustment and Final Total
		// console.log('Adjustment:', this.invoices.adjustment);
		// console.log('Final Total:', this.invoices.total, this.invoices.sub_total, this.invoices.total_taxes, this.total_discount, this.invoices.adjustment);
	}

	changeRecurrence($event = []) {
		if (this.editInvoiceForm.value.recurrence) {
			this.editInvoiceForm.get('recurrence_occurrences').setValidators([Validators.required]);
			this.editInvoiceForm.get('recurrence_pattern').setValidators([Validators.required]);
			this.editInvoiceForm.get('recurrence_occurrences').updateValueAndValidity();
			this.editInvoiceForm.get('recurrence_pattern').updateValueAndValidity();
		} else {
			this.editInvoiceForm.patchValue({
				recurrence_occurrences: null,
				recurrence_pattern: null
			});
			this.editInvoiceForm.get('recurrence_occurrences').clearValidators();
			this.editInvoiceForm.get('recurrence_pattern').clearValidators();
			this.editInvoiceForm.get('recurrence_occurrences').updateValueAndValidity();
			this.editInvoiceForm.get('recurrence_pattern').updateValueAndValidity();
		}
	}

	onSubmit() {
		this.isFormSubmitted = true;
		if (this.editInvoiceForm.invalid) {
			return;
		}

		if (this.itemsArray.length == 0) {
			this.toastr.error(this.translate.instant('invoices.create.error_messages.message5'), this.translate.instant('invoices.title'));
			return;
		}

		let invoiceObj = {
			"id": this.editInvoiceForm.value.id,
			"project_id": this.editInvoiceForm.value.project_id,
			"client_id": this.editInvoiceForm.value.client_id,
			"invoice_date": this.datepipe.transform(this.editInvoiceForm.value.invoice_date, 'yyyy-MM-dd'),
			"due_date": this.editInvoiceForm.value.due_date,
			"status": this.editInvoiceForm.value.status,
			"reference": this.editInvoiceForm.value.reference,
			"invoice_header_information_text": this.editInvoiceForm.value.invoice_header_information_text,
			"adjustment": this.invoices.adjustment,
			"discount_type": this.editInvoiceForm.value.discount_type,
			"discount": this.invoices.discount,
			"recurrence": this.editInvoiceForm.value.recurrence,
			"recurrence_pattern": this.editInvoiceForm.value.recurrence_pattern,
			"recurrence_occurrences": this.editInvoiceForm.value.recurrence_occurrences,
			"items": this.itemsArray
		}

		this.invoiceService.update(invoiceObj).subscribe(data => {
			this.toastr.success(this.translate.instant('invoices.messages.update'), this.translate.instant('invoices.title'));
			this.router.navigate(['invoices']);
		});
	}

}
