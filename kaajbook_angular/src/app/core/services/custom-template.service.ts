import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpClient } from '@angular/common/http';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class CustomTemplateService {

  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) { } 

  createTemplate(data: any): Observable<any> {
    // console.log('Data to be sent:', data);
    return this.http.post(`${this.apiUrl}/templates`, data);
  }
}
