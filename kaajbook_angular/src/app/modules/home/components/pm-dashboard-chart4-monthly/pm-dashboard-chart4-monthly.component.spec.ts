import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PmDashboardChart4MonthlyComponent } from './pm-dashboard-chart4-monthly.component';

describe('PmDashboardChart4MonthlyComponent', () => {
  let component: PmDashboardChart4MonthlyComponent;
  let fixture: ComponentFixture<PmDashboardChart4MonthlyComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PmDashboardChart4MonthlyComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PmDashboardChart4MonthlyComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
