import { TestBed, inject } from '@angular/core/testing';

import { RefaccionesService } from './refacciones.service';

describe('RefaccionesService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [RefaccionesService]
    });
  });

  it('should be created', inject([RefaccionesService], (service: RefaccionesService) => {
    expect(service).toBeTruthy();
  }));
});
