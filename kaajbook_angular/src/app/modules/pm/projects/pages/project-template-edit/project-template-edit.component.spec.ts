import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ProjectTemplateEditComponent } from './project-template-edit.component';

describe('ProjectTemplateEditComponent', () => {
  let component: ProjectTemplateEditComponent;
  let fixture: ComponentFixture<ProjectTemplateEditComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ProjectTemplateEditComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ProjectTemplateEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
