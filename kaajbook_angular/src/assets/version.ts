// clolor classes
// class="badge badge-success"
// class="badge badge-warning"
// class="badge badge-danger"
// class="badge badge-secondary"
// class="badge badge-light"
// class="badge badge-dark"

const versionLog = {
    currentVersion: "1.2.9",
    activities: [
          {
            released: false,
            version: "VERSION 1.3.0 -",
            releaseDate: "RELEASED  -_-",
            description: `
            <b>Status</b>: <span class="badge badge-light">Coming Soon</span><br>
            <b>Feature</b>: 
            <ul>
            <li>-</li>
            </ul>
          `,
        },
        {
          released: true,
          version: "VERSION 1.2.9 -",
          releaseDate: "RELEASED  07-07-2024",
          description: `
          <b>Status</b>: <span class="badge badge-success">Released</span><br>
          <b>Feature</b>: 
          <ul>
          <li>Dashboard: add monthly project budget, cost & different (Figure 2)</li>
          <li>Dashboard: transpose PROJECT BILL chart data</li>
          <li>project template: stability, sequential upload, file size fixed, processing toast message</li>
          <li>project template: refresh the page after create</li>
          <li>Invoice payment: remove demo payment gateway redirection</li>
          <li>Invoice change: inv info font, table line width and alignment</li>
          </ul>
        `,
        },
        {
            released: true,
            version: "VERSION 1.2.8 -",
            releaseDate: "RELEASED  10-06-2024",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
             <li>Invoice box color change, change allignment, replace 'discount' with less</li>
             <li>Project-report: ranged search by start - end Date</li>
             <li>Project-report: Download all search results at once (select show all)</li>
             <li>SMS Notification: Send SMS to client on invoice generation 
              (New Job bill: --, Previous Due: all pre inv, Click Like: --)</li>
             <li>Landing page: Stop change on navigation</li>
             <li>Data show limit/page: add 'show all' item in each page</li>
             <li>Add notification length on top</li>
             <li>download dashboard "PROJECT BILL" chart data as xlsx</li>
            </ul>
          `,
        },
        {
          released: true,
          version: "VERSION 1.2.7 -",
          releaseDate: "RELEASED 3-04-2024",
          description: `
          <b>Status</b>: <span class="badge badge-success">Released</span><br>
          <b>Feature</b>: 
          <ul>
            <li>Can remove all notification at once</li>
            <li>Report -> budget precision 2 decimal</li>
            <li>Show all tasks image alongside total task in template list</li>
            <li>Add image file size limit to 1MB and require template name</li>
            <li>Add project name and start/end date on Project->task</li>
          </ul>
        `,
      },
        {
          released: true,
          version: "VERSION 1.2.6 -",
          releaseDate: "RELEASED 19-03-2024",
          description: `
          <b>Status</b>: <span class="badge badge-success">Released</span><br>
          <b>Feature</b>: 
          <ul>
            <li>Can create Porject Template with attached image</li>
            <li>Add task logo on Project => Task</li>
          </ul>
        `,
      },
        {
            released: true,
            version: "VERSION 1.2.5 -",
            releaseDate: "RELEASED 29-02-2024",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
              <li>Add invoice project name in invoice view/details</li>
              <li>Add project ID to respective invoice in invoice list</li>
              <li>Add the total invoice(n) amount in the Dashboard invoice widget</li>
              <li>Dashboard hide -> Tasks status, Defects, Estimates & Re-arrange dashboard component chart order</li>
              <li>Hide the project template from the client view</li>
              <li>Remove Items and Taxs from client view</li>
              <li>Project report -> search by creator & client fixed</li>
              <li>Invoice edit -> Selected Project name & Customer name not showing Fixed</li>
              <li>Project edit-> Selected Client name, assigned group, status not showing Fixed</li>
              <li>Task edit -> Selected Assign to, Status & Priority not showing Fixed</li>
            </ul>
          `,
        },
        {
            released: true,
            version: "VERSION 1.2.4 -",
            releaseDate: "RELEASED 15-02-2024",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
              <li>By default project status In Progress</li>
              <li>Add project name to invoice</li>
              <li>Add uploadable invoice header image</li>
              <li>Add invoice header info text field</li>
              <li>Can update invoice header info text field</li>
              <li>Hide project report -> 'budget' from client view</li>
              <li>Add download option for download all version log as pdf</li>
              <li>Add pay amount to dashboard client bill report(figure-7)</li>
              <li>Remove invoice -> Items, Taxes permission from client</li>
              <li>Client monthly project (figure-3) data fix & change dashboard figure heading</li>
            </ul>
          `,
        },
        {
            released: true,
            version: "VERSION 1.2.3 -",
            releaseDate: "RELEASED 08-02-2024",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
              <li>Get dashboard data for client and project manager</li>
              <li>Add dashboard figure name</li>
              <li>admin, pm, staff, client -> if assign to a project, get this project dashboard data</li>
            </ul>
          `,
        },
        {
            released: true,
            version: "VERSION 1.2.2 -",
            releaseDate: "RELEASED 31-01-2024",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
              <li>Download Project all attachment</li>
              <li>Add invoice header image (hard coded)</li>
              <li>Dashboard unassigned duplicate data fix</li>
              <li>Reducing columns for tasks report to provide a summary view</li>
              <li>Reducing columns from Defects reports for summarized reports and understandable export of the defects</li>
              <li>Add sidebar button for version</li>
            </ul>
          `,
        },
        {
            released: true,
            version: "VERSION 1.2.1 -",
            releaseDate: "RELEASED 21-01-2024",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
              <li>Implemented HTTPS with SSL certificate to enforce secure communication</li>
              <li>Yearly project open,complete chart based on project 1st Client (if no client assing then show unassigned)</li>
              <li>Monthly project open,complete chart based on project Client</li>
              <li>Add Client till date bill report chart</li>
              <li>Remove hour and add bill amount in project report</li>
              <li>Yearly project open,complete chart based on project assigned user</li>
            </ul>
          `,
        },
        {
            released: true,
            version: "VERSION 1.2.0 -",
            releaseDate: "RELEASED 04-01-2024",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
              <li>Remove year from dashboard heading & data precision for 2 decimal</li>
              <li>Project and cost graph order change to horizontal</li>
              <li>Project graph show last 5 year data</li>
            </ul>
          `,
        },
        {
            released: true,
            version: "VERSION 1.1.9 -",
            releaseDate: "RELEASED 04-01-2024",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
              <li>Add all release log</li>
              <li>Separate dashboard project and cost chart</li>
              <li>Add version number on Login</li>
              <li>Dashboard data show based on project start data </li>
            </ul>
          `,
        },
        {
            released: true,
            version: "VERSION 1.1.8 -",
            releaseDate: "RELEASED 26-12-2023",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
              <li>Add version number on sidebar</li>
              <li>Client yearly Bill report on dashboard</li>
              <li>Client yearly project report on dashboard</li>
              <li>Add Monthly project and bill on dashboard</li>
              <li>Add Yearly project and bill on dashboard</li>
              <li>Added 3 charts for month, year, client project report</li>
            </ul>
            `,
        },
        {
            released: true,
            version: "VERSION 1.1.7 -",
            releaseDate: "RELEASED 06-12-2023",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
            <li>Minor chang on custome template</li>
            </ul>
            `,
        },
        {
            released: true,
            version: "VERSION 1.1.6 -",
            releaseDate: "RELEASED 06-12-2023",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
              <li>Add create template button</li>
              <li>User can create template</li>
              <li>User can update template</li>
              <li>User can delete template</li>
              <li>User can view all template </li>
              <li>Add template delete warning</li>
              <li>Add template field to select on project create page</li>
              <li>Remove version & billing from project report</li>
              <li>Auto task start date on (task, project) create</li>
              <li>Client Dashboard: add Project/Task/Monthly report</li>
              <li>Auto end date to Task & Project on Complete status</li>
              <li>Fixed taks & project number</li>
            </ul>
          `,
        },
        {
            released: true,
            version: "VERSION 1.1.5 -",
            releaseDate: "RELEASED 11-10-2023",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
              <li>Dashboard => total Project, Task & invoice value fixed.</li>
              <li>Remove email restriction from client</li>
              <li>Project manager Permission change</li>
              <li>Add new template</li>
            </ul>
          `,
        },
        {
            released: true,
            version: "VERSION 1.1.4 -",
            releaseDate: "RELEASED 27-09-2023",
            description: `
          <b>Status</b>: <span class="badge badge-success">Released</span><br>
          <b>Feature</b>: 
          <ul>
            <li>Add action button to update,copy,delete on project task</li>
            <li>Changeable status in Project -> task</li>
            <li>Add comment, attachment, activity inside Project->Task</li>
            <li>Change invoice pdf alignment</li>
          </ul>
        `,
        },
        {
            released: true,
            version: "VERSION 1.1.3 -",
            releaseDate: "RELEASED 18-09-2023",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
              <li>Invoice, payment logo: Uploaded logo & inv resize for company pad.</li>
              <li>New two template added</li>
            </ul>
          `,
        },
        {
            released: true,
            version: "VERSION 1.1.2 -",
            releaseDate: "RELEASED 13-09-2023",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
              <li>Task list: show in ascending order</li>
              <li>Change comment, attachment, activity color</li>
              <li>Remove unique email requirements from create, register, update & imports user.</li>
              <li>Invoice logo only when user upload logo & change favicon image to kaajbook img</li>
              <li>Add payment, invoice widget to project manager dashboard</li>
            </ul>
          `,
        },
        {
            released: true,
            version: "VERSION 1.1.1 -",
            releaseDate: "RELEASED 03-09-2023",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
              <li>Change side menu bar "sales" name as "Billing"</li>
            </ul>
          `,
        },
        {
            released: true,
            version: "VERSION 1.1.0 -",
            releaseDate: "RELEASED 31-08-2023",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
              <li>Remove Payment tab from client</li>
              <li>Dashboard widget/card will be Projects, Tasks, Invoice, Payment for admin and for other users only Project and Task</li>
              <li>Hide payment mode section from invoice details.</li>
              <li>Fixed duplicated/missing/random task ID generate</li>
              <li>Ascending Template Task order</li>
              <li>For invoice payment button show in client module</li>
            </ul>
          `,
        },
        {
            released: true,
            version: "VERSION 1.0.9 -",
            releaseDate: "RELEASED 21-08-2023",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
              <li>By default task status In Progress</li>
              <li>Assign user to template task from project. Assigned 1st user</li>
            </ul>
          `,
        },
        {
            released: true,
            version: "VERSION 1.0.8 -",
            releaseDate: "RELEASED 17-08-2023",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
              <li>Add new template of project</li>
              <li>Change project description</li>
            </ul>
          `,
        },
        {
            released: true,
            version: "VERSION 1.0.7 -",
            releaseDate: "RELEASED 10-08-2023",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
              <li>Remove start, end date & project name from task edit section</li>
            </ul>
          `,
        },
        {
            released: true,
            version: "VERSION 1.0.6 -",
            releaseDate: "RELEASED 08-08-2023",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
              <li>Add project template type option</li>
              <li>Auto task generate on project create</li>
              <li>Hide task/project details, description & increase comment section width</li>
              <li>Hide defects, incident from Project, dashboard, dashboard widget</li>
            </ul>
          `,
        },
        {
            released: true,
            version: "VERSION 1.0.5 -",
            releaseDate: "RELEASED 13-07-2023",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
              <li>Inside task list, add project name with respective task after ID.</li>
              <li>Comments visible by default</li>
              <li>Reduce invoice logo size</li>
            </ul>
          `,
        },
        {
            released: true,
            version: "VERSION 1.0.4 -",
            releaseDate: "RELEASED 10-07-2023",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br> 
            <b>Feature</b>:
            <ul>
              <li>Task section order chang and remove hour.</li>
            </ul>
        `,
        },
        {
            released: true,
            version: "VERSION 1.0.3 -",
            releaseDate: "RELEASED 11-06-2023",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br> 
            <b>Feature</b>:
            <ul>
              <li>Task, Project & Comments details layout & position change</li>
              <li>In Projects & Task details, show total comments, attachments, activities number</li>
              <li>Making Task & Project Details => ID, Task & Project Clickable.</li>
            </ul>
      `,
        },
        {
            released: true,
            version: "VERSION 1.0.2 -",
            releaseDate: "RELEASED 28-05-2023",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br> 
            <b>Feature</b>:
            <ul>
              <li>Chang Logo color</li>
              <li>Change 'task status' position in task -> list view </li>
             </ul>
    `,
        },
        {
            released: true,
            version: "VERSION 1.0.1 -",
            releaseDate: "RELEASED 17-05-2023",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
              <li>Add kaajbook logo</li>
              <li>Change sidebar layout</li>
              <li>Change background color</li>
            </ul>
  `,
        },
        {
            released: true,
            version: "VERSION 1.0.0 -",
            releaseDate: "RELEASED 14-05-2023",
            description: `
            <b>Status</b>: <span class="badge badge-success">Released</span><br>
            <b>Feature</b>: 
            <ul>
              <li>First Release</li>
            </ul>
        `,
        },
    ],
};

export default versionLog;
