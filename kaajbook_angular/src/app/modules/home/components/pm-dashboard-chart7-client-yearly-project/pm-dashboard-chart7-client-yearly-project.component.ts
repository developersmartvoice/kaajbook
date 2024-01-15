import { Component, OnInit, Input } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { NgxChartsModule } from '@swimlane/ngx-charts';

@Component({
  selector: 'app-pm-dashboard-chart7-client-yearly-project',
  templateUrl: './pm-dashboard-chart7-client-yearly-project.component.html',
  styleUrls: ['./pm-dashboard-chart7-client-yearly-project.component.scss']
})
export class PmDashboardChart7ClientYearlyProjectComponent implements OnInit {

	@Input() yearlyReport: any;
	barChartLabels: string[] = [];
	currentDate : Date = new Date();
	barChartType = 'bar';
	barChartLegend = true;
	invoice = [];
	project_open = [];
	project_complete = [];
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
				min: .9,
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
		// this.barChartLabels = this.translate.instant('months');
		// this.barChartLabels = [ 'client4', 'client5', 'client6', 'client7', 'client8', 'client9', 'client10', 'client11', 'client12'];

	}

	ngOnInit() {
		this.renderChart();
	}

	renderChart() {
		let pmData = [];
		// Get the keys (years) of yearly_project
		const yearlyProjectKeys = Object.keys(this.yearlyReport.yearly_project);

		// Get the last year dynamically
		const thisYear = yearlyProjectKeys[yearlyProjectKeys.length - 1];

		// add dummy user for unassigned project
		this.yearlyReport.all_invoice_client_user.all_users.push( {
			"id": "Unassign",
			"username": "Unassigned",
			"email": " ",
			"full_name": " "
		},);

		this.yearlyReport.yearly_project[thisYear].project_id.forEach((project_id, index) => {

		let current_project_status = this.yearlyReport.yearly_project[thisYear].project_status[index];
		let current_project_client = this.yearlyReport.yearly_project[thisYear].project_client[index];
		
 		this.yearlyReport.all_invoice_client_user.all_users.forEach(user => {
			if (user.id == current_project_client) {

		// Update pmData for the current user_id
		const existingUserIndex = pmData.findIndex(user => user.user_id === current_project_client);
          let project_complete_count = 0;
            if (current_project_status == 5) {
              project_complete_count = 1;
            }

					if (existingUserIndex === -1) {
						// If the user with the given user_id doesn't exist, add it
            
						pmData.push({
							user_id: current_project_client,
							project_open: 1,
							project_complete: project_complete_count,
						});
					} else {
						// If the user with the given user_id already exists, update its values
						pmData[existingUserIndex].project_open += 1;
						pmData[existingUserIndex].project_complete += project_complete_count;
					}		
				}
			});
		});

		pmData.forEach(element => {
			this.project_open.push(Number(element.project_open.toFixed(2)));
			this.project_complete.push(Number(element.project_complete.toFixed(2)));
 

			this.yearlyReport.all_invoice_client_user.all_users.forEach(user => {
				if (user.id == element.user_id) {
					this.barChartLabels.push(user.username); 
				}
 			});

		});
			
		this.barChartData = [
			{ data: this.project_open, label: this.translate.instant('projects.project_open') },
			{ data: this.project_complete, label: this.translate.instant('projects.project_complete') },

 		];
	}
}
