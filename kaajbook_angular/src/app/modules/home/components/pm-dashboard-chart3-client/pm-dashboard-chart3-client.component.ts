import { Component, OnInit, Input } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { NgxChartsModule } from '@swimlane/ngx-charts';

@Component({
  selector: 'app-pm-dashboard-chart3-client',
  templateUrl: './pm-dashboard-chart3-client.component.html',
  styleUrls: ['./pm-dashboard-chart3-client.component.scss']
})
export class PmDashboardChart3ClientComponent implements OnInit {


	@Input() monthlyReport: any;
	barChartLabels: string[] = [];
	currentDate : Date = new Date();
	barChartType = 'bar';
	barChartLegend = true;
	tasks = [];
	defects = [];
	incidents = [];
	barChartData: any[] = [];
	barChartOptions: any = {
		scaleShowVerticalLines: false,
		responsive: true,
		maintainAspectRatio: false,

		tooltips: {
			callbacks: {
			  label: function (tooltipItem, data) {
				let label = data.datasets[tooltipItem.datasetIndex].label || '';
		
				if (label === 'Price') {
				  label += ': $' + tooltipItem.yLabel;
				} else {
				  label += ': ' + tooltipItem.yLabel;
				}
		
				return label;
			  }
			}
		}
  };
  barChartColors: Array<any> = [{
		  backgroundColor: 'rgba(255, 141, 96, 0.8)',
			borderColor: 'rgba(148,159,177,1)',
			pointBackgroundColor: 'rgba(148,159,177,1)',
			pointBorderColor: '#fff',
			pointHoverBackgroundColor: '#fff',
			pointHoverBorderColor: 'rgba(148,159,177,0.8)'
		}, {
			backgroundColor: 'rgba(92, 184, 92, 0.7)',
			borderColor: 'rgba(148,159,177,1)',
			pointBackgroundColor: 'rgba(148,159,177,1)',
			pointBorderColor: '#fff',
			pointHoverBackgroundColor: '#fff',
			pointHoverBorderColor: 'rgba(148,159,177,0.8)'
		}
	];

	constructor(public translate: TranslateService) {
		// this.barChartLabels = this.translate.instant('months');
		this.barChartLabels = ['client1', 'client2', 'client3', 'client4', 'client5', 'client6', 'client7', 'client8', 'client9', 'client10', 'client11', 'client12'];

	}

	ngOnInit() {
		this.renderChart();
	}

	renderChart() {
		for(let iRow in this.monthlyReport) {
			this.tasks.push(this.monthlyReport[iRow].tasks);
			this.defects.push(this.monthlyReport[iRow].defects);
			// this.incidents.push(this.monthlyReport[iRow].incidents);
		}

		this.barChartData = [
			{ data: this.tasks, label: this.translate.instant('projects.title') },
			{ data: this.defects, label: this.translate.instant('projects.title_cost') },
			// { data: this.incidents, label: this.translate.instant('incidents.title') }
		];
	}


}
