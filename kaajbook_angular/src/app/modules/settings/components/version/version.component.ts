import { Component, OnInit, Input, ViewChild } from "@angular/core";
import { PerfectScrollbarConfigInterface } from "ngx-perfect-scrollbar";
import versionLog from 'src/assets/version';

@Component({
    selector: "app-version",
    templateUrl: "./version.component.html",
    styleUrls: ["./version.component.scss"],
})
export class VersionComponent implements OnInit {
    public scrollConfig: PerfectScrollbarConfigInterface = {};


    versionLog = versionLog;

    constructor() {}

    ngOnInit() {}
}
