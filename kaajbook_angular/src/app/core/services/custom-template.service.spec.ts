import { TestBed } from '@angular/core/testing';

import { CustomTemplateService } from './custom-template.service';

describe('CustomTemplateService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: CustomTemplateService = TestBed.get(CustomTemplateService);
    expect(service).toBeTruthy();
  });
});
