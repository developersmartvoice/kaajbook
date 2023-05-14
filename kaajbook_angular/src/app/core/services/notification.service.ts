import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class NotificationService {

    private apiUrl = environment.apiUrl;

    constructor(private http: HttpClient) { }

    getNotifications(length: number) {
        return this.http.post(`${this.apiUrl}/api/get-notifications`, { length: length });
    }

	delete(id: number) {
		return this.http.post(`${this.apiUrl}/api/notifications/delete`, { id: id });
	}
}
