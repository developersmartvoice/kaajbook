import {
	Component,
	OnInit,
	Input,
	ViewChild
} from '@angular/core';
import { PerfectScrollbarConfigInterface } from 'ngx-perfect-scrollbar';

@Component({
  selector: 'app-version',
  templateUrl: './version.component.html',
  styleUrls: ['./version.component.scss']
})
export class VersionComponent implements OnInit {
  public scrollConfig: PerfectScrollbarConfigInterface = {};
	// @Input() task;
	// @Input() loginUser: any;
	// @Input() apiUrl;
  task = {
    "activities": [
        {
            "id": 375,
            "user_id": 1,
            "module": "Task",
            "self_parent_id": 73,
            "module_field_id": 73,
            "action": "PUT",
            "description": "Task Updated : <b>Container sealing</b>",
            "ip_address": "127.0.0.1",
            "diff_for_humans": "2 weeks before",
            "created_at": "2023-12-10 16:22:02",
            "updated_at": "2023-12-10 16:22:02",
            "deleted_at": null,
            "user": {
                "id": 1,
                "firstname": "FirstName",
                "lastname": "LastName",
                "avatar": null,
                "full_name": "FirstName LastName"
            }
        },
        {
            "id": 226,
            "user_id": 1,
            "module": "Task",
            "self_parent_id": 73,
            "module_field_id": 73,
            "action": "PUT",
            "description": "Task Updated : <b>Container sealing</b>",
            "ip_address": "127.0.0.1",
            "diff_for_humans": "1 month before",
            "created_at": "2023-10-29 10:54:23",
            "updated_at": "2023-10-29 10:54:23",
            "deleted_at": null,
            "user": {
                "id": 1,
                "firstname": "FirstName",
                "lastname": "LastName",
                "avatar": null,
                "full_name": "FirstName LastName"
            }
        },
        {
            "id": 225,
            "user_id": 1,
            "module": "Task",
            "self_parent_id": 73,
            "module_field_id": 73,
            "action": "PUT",
            "description": "Task Updated : <b>Container sealing</b>",
            "ip_address": "127.0.0.1",
            "diff_for_humans": "1 month before",
            "created_at": "2023-10-29 10:53:41",
            "updated_at": "2023-10-29 10:53:41",
            "deleted_at": null,
            "user": {
                "id": 1,
                "firstname": "FirstName",
                "lastname": "LastName",
                "avatar": null,
                "full_name": "FirstName LastName"
            }
        },
        {
            "id": 224,
            "user_id": 1,
            "module": "Task",
            "self_parent_id": 73,
            "module_field_id": 73,
            "action": "POST",
            "description": "Task Status Changed : <b>Container sealing</b>",
            "ip_address": "127.0.0.1",
            "diff_for_humans": "1 month before",
            "created_at": "2023-10-29 10:53:10",
            "updated_at": "2023-10-29 10:53:10",
            "deleted_at": null,
            "user": {
                "id": 1,
                "firstname": "FirstName",
                "lastname": "LastName",
                "avatar": null,
                "full_name": "FirstName LastName"
            }
        },
        {
            "id": 223,
            "user_id": 1,
            "module": "Task",
            "self_parent_id": 73,
            "module_field_id": 73,
            "action": "PUT",
            "description": "Task Updated : <b>Container sealing</b>",
            "ip_address": "127.0.0.1",
            "diff_for_humans": "1 month before",
            "created_at": "2023-10-29 10:52:13",
            "updated_at": "2023-10-29 10:52:13",
            "deleted_at": null,
            "user": {
                "id": 1,
                "firstname": "FirstName",
                "lastname": "LastName",
                "avatar": null,
                "full_name": "FirstName LastName"
            }
        },
        {
            "id": 221,
            "user_id": 1,
            "module": "Task",
            "self_parent_id": 73,
            "module_field_id": 73,
            "action": "POST",
            "description": "Task Added : <b>Container sealing</b>",
            "ip_address": "127.0.0.1",
            "diff_for_humans": "1 month before",
            "created_at": "2023-10-29 10:51:08",
            "updated_at": "2023-10-29 10:51:08",
            "deleted_at": null,
            "user": {
                "id": 1,
                "firstname": "FirstName",
                "lastname": "LastName",
                "avatar": null,
                "full_name": "FirstName LastName"
            }
        }
    ],
}

	constructor() {}

	ngOnInit() {}
}
