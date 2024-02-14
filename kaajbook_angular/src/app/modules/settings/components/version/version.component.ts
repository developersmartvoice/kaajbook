import { Component, OnInit, Input, ViewChild } from "@angular/core";
import { PerfectScrollbarConfigInterface } from "ngx-perfect-scrollbar";
import versionLog from 'src/assets/version';
import * as jsPDF from 'jspdf';
import html2canvas from 'html2canvas';
import html2pdf from 'html2pdf.js';

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
    downloadAllVersionLogAsPDF() {
        const content = document.getElementById('versionLogs').innerHTML;
        const downloadDateTime = new Date().toLocaleString(); // Get the current date and time

        const contentWithDateTime = `
            <div>
                <p>Date and Time: ${downloadDateTime}</p>
                ${content}
            </div>
        `;

        const options = {
            filename: 'version_logs.pdf',
            image: { type: 'jpeg', quality: 0.9 },
            html2canvas: { scale: 3 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        html2pdf().set(options).from(contentWithDateTime).save();
    }

}
