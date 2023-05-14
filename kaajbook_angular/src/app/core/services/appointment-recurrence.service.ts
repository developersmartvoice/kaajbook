import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

import { environment } from '../../../environments/environment';

@Injectable({
    providedIn: 'root'
})
export class AppointmentRecurrenceService {

    private apiUrl = environment.apiUrl;

    constructor(private http: HttpClient) { }

    changeStatus(appointmentRecurrence: any) {
        return this.http.post(`${this.apiUrl}/api/appointment-recurrence/${appointmentRecurrence.id}/change-status`, { status: appointmentRecurrence.status });
    }
}
