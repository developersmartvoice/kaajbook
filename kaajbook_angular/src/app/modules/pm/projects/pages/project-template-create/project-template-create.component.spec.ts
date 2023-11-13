import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ProjectTemplateCreateComponent } from './project-template-create.component';

describe('ProjectTemplateCreateComponent', () => {
  let component: ProjectTemplateCreateComponent;
  let fixture: ComponentFixture<ProjectTemplateCreateComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ProjectTemplateCreateComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ProjectTemplateCreateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
