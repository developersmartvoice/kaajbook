import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { FormGroup, FormBuilder, Validators, FormControl } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
import { TranslateService } from '@ngx-translate/core';

import { InvoiceSettingService } from '../../../../core/services/invoice-setting.service';

import { environment } from '../../../../../environments/environment';

import * as Dropzone from 'dropzone';

@Component({
  selector: 'app-invoice-settings',
  templateUrl: './invoice-settings.component.html',
  styleUrls: ['./invoice-settings.component.scss']
})
export class InvoiceSettingsComponent implements OnInit {

	private apiUrl = environment.apiUrl;

	invoiceSettingsForm: FormGroup;
	isFormSubmitted = false;
	isFormLoaded = false;
	isLogoLoaded = true;
	isHeaderLogoLoaded = true;
	setting: any;

	@ViewChild('logodropzone', { static: false }) logodropzone: ElementRef;
	@ViewChild('header_logodropzone', { static: false }) header_logodropzone: ElementRef;

	constructor(
		private formBuilder: FormBuilder,
		private invocieSettingService: InvoiceSettingService,
		private toastr: ToastrService,
		public translate: TranslateService
	) { }

	ngOnInit() {
		this.getInvoiceSetting();
	}

	getInvoiceSetting() {
		this.invocieSettingService.getAll().subscribe(data => {
			this.setting = data;
			this.loadForm();
			setTimeout(() => {
				this.loadDropzone();
			});
		});
	}

	loadForm() {
		this.invoiceSettingsForm = this.formBuilder.group({
			invoice_prefix: [this.setting.invoice_prefix, [Validators.required, Validators.maxLength(10)]],
			due_after: [this.setting.due_after, [Validators.required]],
			client_note: [this.setting.client_note],
			terms_conditions: [this.setting.terms_conditions],
			auto_remind: [this.setting.auto_remind],
			auto_remind_after: [this.setting.auto_remind_after],
			invoice_logo: [this.setting.invoice_logo],
			invoice_header: [this.setting.invoice_header],
			gst_number: [this.setting.gst_number],
			show_gst_number: [this.setting.show_gst_number]
		});
		this.isFormLoaded = true;
	}

	loadDropzone() {
		let that = this;
		new Dropzone(this.logodropzone.nativeElement, {
			url: 'https://httpbin.org/post',
			maxFiles: 1,
			clickable: true,
			acceptedFiles: 'image/*',
			createImageThumbnails: true,
			init: function() {
				this.on('addedfile', function(file) {
					const removeButton = Dropzone.createElement("<button class=\'btn btn-sm btn-block\'>" + that.translate.instant('common.remove_file') + "</button>");
					const _this = this;
					removeButton.addEventListener('click', function(e) {
						e.preventDefault();
						e.stopPropagation();
						_this.removeFile(file);
					});

					file.previewElement.appendChild(removeButton);
					if (file) {
						let reader = new FileReader();
						reader.onload = (e) => {
							that.invoiceSettingsForm.patchValue({ invoice_logo: reader.result });
							this.isLogoLoaded = false;
						}
						reader.readAsDataURL(file);
					}
				});

				this.on('removedfile', function(file) {
					that.invoiceSettingsForm.patchValue({ invoice_logo: null });
				});

				this.on('error', function(file, message: any) {
					if (file) {
						that.toastr.error(message);
					}
				});
			}
		}),
		new Dropzone(this.header_logodropzone.nativeElement, {
			url: 'https://httpbin.org/post',
			maxFiles: 1,
			clickable: true,
			acceptedFiles: 'image/*',
			createImageThumbnails: true,
			init: function() {
				this.on('addedfile', function(file) {
					const removeButton = Dropzone.createElement("<button class=\'btn btn-sm btn-block\'>" + that.translate.instant('common.remove_file') + "</button>");
					const _this = this;
					removeButton.addEventListener('click', function(e) {
						e.preventDefault();
						e.stopPropagation();
						_this.removeFile(file);
					});

					file.previewElement.appendChild(removeButton);
					if (file) {
						let reader = new FileReader();
						reader.onload = (e) => {
							that.invoiceSettingsForm.patchValue({ invoice_header: reader.result });
							this.isHeaderLogoLoaded = false;
						}
						reader.readAsDataURL(file);
					}
				});

				this.on('removedfile', function(file) {
					that.invoiceSettingsForm.patchValue({ invoice_header: null });
				});

				this.on('error', function(file, message: any) {
					if (file) {
						that.toastr.error(message);
					}
				});
			}
		})
	}

	removeDropzoneImage() {
		this.isLogoLoaded = false;
		this.setting.invoice_logo = null;
		this.invoiceSettingsForm.patchValue({ invoice_logo: null });
	}
	removeDropzoneImageHeader() {
		this.isHeaderLogoLoaded = false;
		this.setting.invoice_header = null;
		this.invoiceSettingsForm.patchValue({ invoice_header: null });
	}

	get invoiceSetting() { return this.invoiceSettingsForm.controls; }

	onSubmit() {
		this.isFormSubmitted = true;
		if (this.invoiceSettingsForm.invalid) {
			return;
		}

		this.invocieSettingService.create(this.invoiceSettingsForm.value).subscribe(data => {
			this.toastr.success(this.translate.instant('settings.messages.update'), this.translate.instant('settings.title'));
		});
	}

}
