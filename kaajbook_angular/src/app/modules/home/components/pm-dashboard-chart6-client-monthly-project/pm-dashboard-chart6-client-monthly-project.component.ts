import { Component, OnInit, Input } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { NgxChartsModule } from '@swimlane/ngx-charts';

@Component({
  selector: 'app-pm-dashboard-chart6-client-monthly-project',
  templateUrl: './pm-dashboard-chart6-client-monthly-project.component.html',
  styleUrls: ['./pm-dashboard-chart6-client-monthly-project.component.scss']
})
export class PmDashboardChart6ClientMonthlyProjectComponent implements OnInit {

	@Input() yearlyReport: any;
	barChartLabels: string[] = [];
	currentDate : Date = new Date();
	barChartType = 'bar';
	barChartLegend = true;
	invoice = [];
	invoice_bill = [];
	invoice_due = [];
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
		let clientData = [];
		// Get the keys (years) of yearly_project
		const yearlyProjectKeys = Object.keys(this.yearlyReport.yearly_project);

		// Get the last year dynamically
		const thisYear = yearlyProjectKeys[yearlyProjectKeys.length - 1];
		console.log(this.yearlyReport);

		this.yearlyReport.yearly_project[thisYear].project_id.forEach(project_id => {
			this.yearlyReport.all_invoice_client.all_invoices.forEach(invoice => {
				if (invoice.project_id == project_id) {

					// Update clientData for the current client_id
					const existingClientIndex = clientData.findIndex(client => client.client_id === invoice.client_id);

					if (existingClientIndex === -1) {
						// If the client with the given client_id doesn't exist, add it
						clientData.push({
							client_id: invoice.client_id,
							total_amount: Number(invoice.total_amount),
							total_due_amount: Number(invoice.total_due_amount),
							total_invoice: 1 
						});
					} else {
						// If the client with the given client_id already exists, update its values
						clientData[existingClientIndex].total_amount += Number(invoice.total_amount);
						clientData[existingClientIndex].total_due_amount += Number(invoice.total_due_amount);
						clientData[existingClientIndex].total_invoice += 1; 
					}		
				}
			});
		});

		clientData.forEach(element => {
			this.invoice.push(Number(element.total_invoice));
			this.invoice_bill.push(Number(element.total_amount.toFixed(2)));
			this.invoice_due.push(Number(element.total_due_amount.toFixed(2)));
 

			this.yearlyReport.all_invoice_client.all_clients.forEach(client => {
				if (client.id == element.client_id) {
					this.barChartLabels.push(client.username); 
				}
 			});

		});
			
		this.barChartData = [
 			{ data: this.invoice, label: this.translate.instant('projects.title_invoice') },
			{ data: this.invoice_bill, label: this.translate.instant('projects.title_bill') },
			{ data: this.invoice_due, label: this.translate.instant('projects.title_due') },

 		];
	}	

}
