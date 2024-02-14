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
	project = [];
	project_bill = [];
	incidents = [];
	barChartDataProject: any[] = [];
	barChartDataProjectBill: any[] = [];
	barChartOptions: any = {
		scaleShowVerticalLines: false,
		responsive: true,
		maintainAspectRatio: false,

		scales: {
			yAxes: [{
			  type: 'linear', // Set the y-axis scale to logarithmic
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
				  label += ': ৳' + tooltipItem.yLabel;
				} else {
				  label += ': ' + tooltipItem.yLabel;
				}
		
				return label;
			  }
			}
		  }
	};
	barChartColorsProject: Array<any> = [{
			backgroundColor: 'rgba(255, 141, 96, 0.8)',
			borderColor: 'rgba(148,159,177,1)',
			pointBackgroundColor: 'rgba(148,159,177,1)',
			pointBorderColor: '#fff',
			pointHoverBackgroundColor: '#fff',
			pointHoverBorderColor: 'rgba(148,159,177,0.8)'
		}
	];

	barChartColorsProjectBill: Array<any> = [{
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

		for (let iRow in this.monthlyReport.monthly_project) {
			let projectCostForCurrentProject = 0;  // Initialize the project cost for the current project_id
	
			this.monthlyReport.monthly_project[iRow].project_id.forEach(project_id => {
				this.monthlyReport.all_invoice_client_user.all_invoices.forEach(invoice => {
					if (invoice.project_id == project_id) {
						projectCostForCurrentProject += Number(invoice.total_amount); // Accumulate project cost
					}
				});
			});
	
			this.project.push(this.monthlyReport.monthly_project[iRow].project_id.length);
			this.project_bill.push(Number(projectCostForCurrentProject.toFixed(2))); // Push accumulated project cost for the current project_id
		}

		this.barChartDataProject = [
			{ data: this.project, label: this.translate.instant('projects.title') },
			// { data: this.project_bill, label: this.translate.instant('projects.title_cost') },
		];

		this.barChartDataProjectBill = [
			// { data: this.project, label: this.translate.instant('projects.title') },
			{ data: this.project_bill, label: this.translate.instant('projects.title_cost') },
		];
	}



}
