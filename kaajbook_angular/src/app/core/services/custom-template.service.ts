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

  // Fetch all project templates
  getAllTemplates(): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/api/project-templates`);
  }

  // Create a new project template
  createTemplate(templateData: any): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}/api/project-templates`, templateData);
  }

  // Get details of a specific project template by ID
  getTemplateById(templateId: number): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/api/project-templates/id/${templateId}`);
  }

  // Update a project template by ID
  updateTemplate(templateId: number, templateData: any): Observable<any> {
    return this.http.put<any>(`${this.apiUrl}/api/project-templates/${templateId}`, templateData);
  }

  // Delete a project template by ID
  deleteTemplate(templateId: number): Observable<any> {
    // console.log(templateId);
    return this.http.delete<any>(`${this.apiUrl}/api/project-templates/${templateId}`);
  }
}
