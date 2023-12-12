import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PmDashboardChart3ClientBillComponent } from './pm-dashboard-chart3-client-bill.component';

describe('PmDashboardChart3ClientBillComponent', () => {
  let component: PmDashboardChart3ClientBillComponent;
  let fixture: ComponentFixture<PmDashboardChart3ClientBillComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PmDashboardChart3ClientBillComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PmDashboardChart3ClientBillComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
