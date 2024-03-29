import {
	Component,
	OnInit,
	Input,
	EventEmitter,
	Output,
	ViewChild,
	Renderer
} from '@angular/core';

import { editorConfig } from '../../../core/helpers/admin.helper';

@Component({
	selector: 'inline-edit-text-editor',
	templateUrl: './inline-edit-text-editor.component.html',
	styleUrls: ['./inline-edit-text-editor.component.scss']
})

export class InlineEditTextEditorComponent implements OnInit {
	public value: any;
	public isEditView: boolean = false;
	public docEditUnlisten: any;
	@Input() name: string;
	@Input() elementFor: string;
	@Input() fieldValue: string;
	@Input() isRequired: boolean;
	@Input() permission: string;
	@Output() updateValue: EventEmitter<any> = new EventEmitter();
	editorConfig = editorConfig;

	@ViewChild('container', {static: false}) container;

	constructor(private renderer: Renderer) { }

	ngOnInit() {}

	showEditable() {
		this.value = this.fieldValue;
		this.isEditView = true;

		this.docEditUnlisten = this.renderer.listenGlobal('document', 'click', (event) => {
			if (!this.container.nativeElement.contains(event.target)) {
				this.showDetail();
			}
		})
	}

	save() {
		this.isEditView = false;
		this.updateValue.emit(this.value);
	}

	showDetail() {
		this.isEditView = false;
	}
}
