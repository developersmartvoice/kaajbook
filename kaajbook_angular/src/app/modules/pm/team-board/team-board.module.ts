import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HttpClient, HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { TooltipModule,ModalModule } from 'ngx-bootstrap';
import { TranslateModule, TranslateLoader } from '@ngx-translate/core';
import { TranslateHttpLoader } from '@ngx-translate/http-loader';

import { SharedModule } from '../../../shared/shared.module';
import { TeamBoardRoutingModule } from './team-board-routing.module';

import { TeamBoardComponent } from './pages/team-board/team-board.component';

@NgModule({
	declarations: [TeamBoardComponent],
	imports: [
		CommonModule,
		TeamBoardRoutingModule,
		TooltipModule.forRoot(),
		ModalModule.forRoot(),
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

export class TeamBoardModule { }

// Required for AOT compilation
export function HttpLoaderFactory(http: HttpClient) {
	return new TranslateHttpLoader(http, '../assets/i18n/', '.json');
}