import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PmDashboardChart5YearlyComponent } from './pm-dashboard-chart5-yearly.component';

describe('PmDashboardChart5YearlyComponent', () => {
  let component: PmDashboardChart5YearlyComponent;
  let fixture: ComponentFixture<PmDashboardChart5YearlyComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PmDashboardChart5YearlyComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PmDashboardChart5YearlyComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
