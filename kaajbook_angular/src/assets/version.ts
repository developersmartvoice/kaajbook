// clolor classes
// class="badge badge-success"
// class="badge badge-warning"
// class="badge badge-danger"
// class="badge badge-secondary"
// class="badge badge-light"
// class="badge badge-dark"

const versionLog = {
    currentVersion: "1.1.8",
    activities: [
        {
            released: false,
            version: "VERSION 1.2.0 -",
            releaseDate: "RELEASED ---",
            description: `
            <b>Status</b>: <span class="badge badge-light">Coming Soon</span><br>
            <b>Feature</b>: 
            <ul>
              <li>Visual task report status</li>
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
              <li>By default project status In Progress</li>
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
