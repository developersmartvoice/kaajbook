import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClient, HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import {
	ModalModule,
	TooltipModule,
	DatepickerModule,
	BsDatepickerModule,
	BsDropdownModule
} from 'ngx-bootstrap';
import { DataTablesModule } from 'angular-datatables';
import { ExportAsModule } from 'ngx-export-as';
import { NgxEditorModule } from 'ngx-editor'
import { NgSelectModule } from '@ng-select/ng-select';
import { TranslateModule, TranslateLoader } from '@ngx-translate/core';
import { NgxPermissionsModule } from 'ngx-permissions';
import { TranslateHttpLoader } from '@ngx-translate/http-loader';
import { OwlDateTimeModule, OwlNativeDateTimeModule } from 'ng-pick-datetime';
import { CalendarModule, DateAdapter } from 'angular-calendar';
import { DragAndDropModule } from 'angular-draggable-droppable';
import { ResizeEvent } from 'angular-resizable-element';
import { ResizableModule } from 'angular-resizable-element';
import { adapterFactory } from 'angular-calendar/date-adapters/date-fns';

import { AppointmentsRoutingModule } from './appointments-routing.module';
import { SharedModule } from '../../../shared/shared.module';

import { AppointmentsComponent } from './pages/appointments/appointments.component';
import { CreateAppointmentComponent } from './components/create-appointment/create-appointment.component';
import { EditAppointmentComponent } from './components/edit-appointment/edit-appointment.component';
import { AppointmentsListComponent } from './pages/appointments-list/appointments-list.component';
import { AppointmentsDetailComponent } from './pages/appointments-detail/appointments-detail.component';
import { AppointmentRecurrenceComponent } from './components/appointment-recurrence/appointment-recurrence.component';

@NgModule({
	declarations: [
		AppointmentsComponent,
		CreateAppointmentComponent,
		EditAppointmentComponent,
		AppointmentsListComponent,
		AppointmentsDetailComponent,
		AppointmentRecurrenceComponent
	],
	imports: [
		CommonModule,
		AppointmentsRoutingModule,
		DataTablesModule,
		ExportAsModule,
		DragAndDropModule,
		ResizableModule,
		NgSelectModule,
		FormsModule,
		ReactiveFormsModule,
		NgxEditorModule,
		OwlDateTimeModule, 
		OwlNativeDateTimeModule,
		NgxPermissionsModule,
		ModalModule.forRoot(),
		TooltipModule.forRoot(),
		DatepickerModule.forRoot(),
		BsDatepickerModule.forRoot(),
		BsDropdownModule.forRoot(),
		CalendarModule.forRoot({
			provide: DateAdapter,
			useFactory: adapterFactory
		}),
		TranslateModule.forChild({
			loader: {
				provide: TranslateLoader,
				useFactory: (HttpLoaderFactory),
				deps: [HttpClient]
			}
		}),
		SharedModule
	],
	entryComponents: [
		CreateAppointmentComponent,
		EditAppointmentComponent
	],
	providers: []
})

export class AppointmentsModule { }

// Required for AOT compilation
export function HttpLoaderFactory(http: HttpClient) {
	return new TranslateHttpLoader(http, '../assets/i18n/', '.json');
}