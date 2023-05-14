import { TestBed } from '@angular/core/testing';

import { AppointmentRecurrenceService } from './appointment-recurrence.service';

describe('AppointmentRecurrenceService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: AppointmentRecurrenceService = TestBed.get(AppointmentRecurrenceService);
    expect(service).toBeTruthy();
  });
});
