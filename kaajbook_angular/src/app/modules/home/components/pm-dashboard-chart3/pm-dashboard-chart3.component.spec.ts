import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PmDashboardChart3Component } from './pm-dashboard-chart3.component';

describe('PmDashboardChart3Component', () => {
  let component: PmDashboardChart3Component;
  let fixture: ComponentFixture<PmDashboardChart3Component>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PmDashboardChart3Component ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PmDashboardChart3Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
