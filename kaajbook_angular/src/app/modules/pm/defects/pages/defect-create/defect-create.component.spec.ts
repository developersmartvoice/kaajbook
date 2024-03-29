import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DefectCreateComponent } from './defect-create.component';

describe('DefectCreateComponent', () => {
  let component: DefectCreateComponent;
  let fixture: ComponentFixture<DefectCreateComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DefectCreateComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DefectCreateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
