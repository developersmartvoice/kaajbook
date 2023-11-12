import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ProjectTemplateListComponent } from './project-template-list.component';

describe('ProjectTemplateListComponent', () => {
  let component: ProjectTemplateListComponent;
  let fixture: ComponentFixture<ProjectTemplateListComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ProjectTemplateListComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ProjectTemplateListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
