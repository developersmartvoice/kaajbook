import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClient, HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import {
	ModalModule,
	TooltipModule,
	BsDropdownModule,
	DatepickerModule,
	BsDatepickerModule
} from 'ngx-bootstrap';
import { ExportAsModule } from 'ngx-export-as';
import { NgxEditorModule } from 'ngx-editor';
import { NgxPermissionsModule } from 'ngx-permissions';
import { NgSelectModule } from '@ng-select/ng-select';
import { TranslateModule, TranslateLoader } from '@ngx-translate/core';
import { TranslateHttpLoader } from '@ngx-translate/http-loader';
import { DataTablesModule } from 'angular-datatables';

import { EstimatesRoutingModule } from './estimates-routing.module';
import { SharedModule } from '../../../shared/shared.module';

import { EstimateListsComponent } from './pages/estimate-lists/estimate-lists.component';
import { EstimateCreateComponent } from './pages/estimate-create/estimate-create.component';
import { EstimateEditComponent } from './pages/estimate-edit/estimate-edit.component';
import { EstimateViewComponent } from './pages/estimate-view/estimate-view.component';

@NgModule({
	declarations: [
		EstimateListsComponent,
		EstimateCreateComponent,
		EstimateEditComponent,
		EstimateViewComponent
	],
	imports: [
		CommonModule,
		EstimatesRoutingModule,
		FormsModule,
		ReactiveFormsModule,
		NgxPermissionsModule,
		DataTablesModule,
		ExportAsModule,
		NgxEditorModule,
		NgSelectModule,
		ModalModule.forRoot(),
		DatepickerModule.forRoot(),
		BsDatepickerModule.forRoot(),
		TooltipModule.forRoot(),
		BsDropdownModule.forRoot(),
		TranslateModule.forChild({
			loader: {
				provide: TranslateLoader,
				useFactory: (HttpLoaderFactory),
				deps: [HttpClient]
			}
		}),
		SharedModule
	]
})

export class EstimatesModule { }

// Required for AOT compilation
export function HttpLoaderFactory(http: HttpClient) {
	return new TranslateHttpLoader(http, '../assets/i18n/', '.json');
}
