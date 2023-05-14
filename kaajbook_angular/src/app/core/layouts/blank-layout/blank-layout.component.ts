import { Component, OnInit, ViewChild, ElementRef, Renderer2, Input } from '@angular/core';

import { SettingService } from '../../services/setting.service';

@Component({
	selector: 'app-blank-layout',
	templateUrl: './blank-layout.component.html',
	styleUrls: ['./blank-layout.component.scss']
})

export class BlankLayoutComponent  {
	settings: any;

	/**
	 *	@class BlankLayoutComponent
	 *	@constructor
	*/
	constructor(
		private renderer: Renderer2,
		private settingService: SettingService
	) {
		this.getSettings();
	}

	getSettings() {
		this.settingService.getSettings()
			.subscribe(
				data => {
					this.settings = data;

					if(this.settings.theme_layout == 'dark') {
						this.renderer.addClass(document.body, 'dark');
					}
				});
	}
}
