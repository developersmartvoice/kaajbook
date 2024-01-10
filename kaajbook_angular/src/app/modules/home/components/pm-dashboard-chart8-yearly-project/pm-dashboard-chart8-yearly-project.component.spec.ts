import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PmDashboardChart8YearlyProjectComponent } from './pm-dashboard-chart8-yearly-project.component';

describe('PmDashboardChart8YearlyProjectComponent', () => {
  let component: PmDashboardChart8YearlyProjectComponent;
  let fixture: ComponentFixture<PmDashboardChart8YearlyProjectComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PmDashboardChart8YearlyProjectComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PmDashboardChart8YearlyProjectComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
