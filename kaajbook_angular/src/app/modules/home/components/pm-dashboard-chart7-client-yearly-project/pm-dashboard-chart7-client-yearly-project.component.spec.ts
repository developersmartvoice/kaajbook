import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PmDashboardChart7ClientYearlyProjectComponent } from './pm-dashboard-chart7-client-yearly-project.component';

describe('PmDashboardChart7ClientYearlyProjectComponent', () => {
  let component: PmDashboardChart7ClientYearlyProjectComponent;
  let fixture: ComponentFixture<PmDashboardChart7ClientYearlyProjectComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PmDashboardChart7ClientYearlyProjectComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PmDashboardChart7ClientYearlyProjectComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
