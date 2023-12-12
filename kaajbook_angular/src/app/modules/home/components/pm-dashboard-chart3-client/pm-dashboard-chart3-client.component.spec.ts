import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PmDashboardChart3ClientComponent } from './pm-dashboard-chart3-client.component';

describe('PmDashboardChart3ClientComponent', () => {
  let component: PmDashboardChart3ClientComponent;
  let fixture: ComponentFixture<PmDashboardChart3ClientComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PmDashboardChart3ClientComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PmDashboardChart3ClientComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
