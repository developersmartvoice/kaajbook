import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PmDashboardChart6ClientMonthlyProjectComponent } from './pm-dashboard-chart6-client-monthly-project.component';

describe('PmDashboardChart6ClientMonthlyProjectComponent', () => {
  let component: PmDashboardChart6ClientMonthlyProjectComponent;
  let fixture: ComponentFixture<PmDashboardChart6ClientMonthlyProjectComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PmDashboardChart6ClientMonthlyProjectComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PmDashboardChart6ClientMonthlyProjectComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
