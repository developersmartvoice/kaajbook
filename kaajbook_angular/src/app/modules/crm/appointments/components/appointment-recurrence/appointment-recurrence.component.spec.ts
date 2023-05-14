import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AppointmentRecurrenceComponent } from './appointment-recurrence.component';

describe('AppointmentRecurrenceComponent', () => {
  let component: AppointmentRecurrenceComponent;
  let fixture: ComponentFixture<AppointmentRecurrenceComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AppointmentRecurrenceComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AppointmentRecurrenceComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
