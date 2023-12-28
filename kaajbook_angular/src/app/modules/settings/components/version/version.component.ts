import { Component, OnInit, Input, ViewChild } from "@angular/core";
import { PerfectScrollbarConfigInterface } from "ngx-perfect-scrollbar";

@Component({
    selector: "app-version",
    templateUrl: "./version.component.html",
    styleUrls: ["./version.component.scss"],
})
export class VersionComponent implements OnInit {
    public scrollConfig: PerfectScrollbarConfigInterface = {};

    // clolor classes
    // class="badge badge-success"
    // class="badge badge-warning"
    // class="badge badge-danger"
    // class="badge badge-secondary"
    // class="badge badge-light"
    // class="badge badge-dark"

    versionLog = {
        activities: [
            {
                released: false,
                version: "VERSION 1.0.0 -",
                releaseDate: "RELEASED 10-12-2023",
                description: `
                <b>Status</b>: <span class="badge badge-light">Coming Soon</span><br>
                <b>Feature</b>: 
                <ul>
                  <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                  <li>Proin vel justo ut magna fermentum tristique.</li>
                  <li>Sed euismod purus eget ipsum varius, in elementum quam ultricies.</li>
                </ul>
              `,
            },
            {
                released: true,
                version: "VERSION 1.0.0 -",
                releaseDate: "RELEASED 10-12-2023",
                description: `
                <b>Status</b>: <span class="badge badge-success">Completed</span><br> 
                <b>Feature</b>:
                <ul>
                  <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                  <li>Proin vel justo ut magna fermentum tristique.</li>
                  <li>Sed euismod purus eget ipsum varius, in elementum quam ultricies.</li>
                </ul>
            `,
            },
            {
                released: true,
                version: "VERSION 1.0.0 -",
                releaseDate: "RELEASED 10-12-2023",
                description: `
                <b>Status</b>: <span class="badge badge-success">Completed</span><br> 
                <b>Feature</b>:
                <ul>
                  <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                  <li>Proin vel justo ut magna fermentum tristique.</li>
                  <li>Sed euismod purus eget ipsum varius, in elementum quam ultricies.</li>
                </ul>
          `,
            },
            {
                released: true,
                version: "VERSION 1.0.0 -",
                releaseDate: "RELEASED 10-12-2023",
                description: `
                <b>Status</b>: <span class="badge badge-success">Completed</span><br> 
                <b>Feature</b>:
                <ul>
                  <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                  <li>Proin vel justo ut magna fermentum tristique.</li>
                  <li>Sed euismod purus eget ipsum varius, in elementum quam ultricies.</li>
                </ul>
        `,
            },
            {
                released: true,
                version: "VERSION 1.0.0 -",
                releaseDate: "RELEASED 10-12-2023",
                description: `
                <b>Status</b>: <span class="badge badge-success">Completed</span><br>
                <b>Feature</b>: 
                <ul>
                  <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                  <li>Proin vel justo ut magna fermentum tristique.</li>
                  <li>Sed euismod purus eget ipsum varius, in elementum quam ultricies.</li>
                </ul>
      `,
            },
            {
                released: true,
                version: "VERSION 1.0.0 -",
                releaseDate: "RELEASED 10-12-2023",
                description: `
                <b>Status</b>: <span class="badge badge-success">Completed</span><br>
                <b>Feature</b>: 
                <ul>
                  <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                  <li>Proin vel justo ut magna fermentum tristique.</li>
                  <li>Sed euismod purus eget ipsum varius, in elementum quam ultricies.</li>
                </ul>
            `,
            },
        ],
    };

    constructor() {}

    ngOnInit() {}
}
