import { Component, OnInit, Input } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { NgxChartsModule } from '@swimlane/ngx-charts';

@Component({
  selector: 'app-pm-dashboard-chart4-monthly',
  templateUrl: './pm-dashboard-chart4-monthly.component.html',
  styleUrls: ['./pm-dashboard-chart4-monthly.component.scss']
})
export class PmDashboardChart4MonthlyComponent implements OnInit {

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

		scales: {
			yAxes: [{
			  type: 'logarithmic', // Set the y-axis scale to logarithmic
			  ticks: {
				maxTicksLimit: 10, 
				callback: function (value, index, values) {
				  return Number(value.toString()); // Necessary to display values as numbers on the axis
				}
			  }
			}]
		  },

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
		this.barChartLabels = this.translate.instant('months');
	}

	ngOnInit() {
		this.renderChart();
	}

	renderChart() {
		for(let iRow in this.monthlyReport) {
			this.tasks.push(this.monthlyReport[iRow].projects);
			this.defects.push(this.monthlyReport[iRow].project_bill);
			// this.incidents.push(this.monthlyReport[iRow].incidents);
		}

		this.barChartData = [
			{ data: this.tasks, label: this.translate.instant('projects.title') },
			{ data: this.defects, label: this.translate.instant('projects.title_cost') },
			// { data: this.incidents, label: this.translate.instant('incidents.title') }
		];
	}



}
