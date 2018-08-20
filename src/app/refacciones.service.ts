import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

interface myData {
  message: Object,
  success: boolean
}

@Injectable({
  providedIn: 'root'
})

export class RefaccionesService {

  constructor(private http: HttpClient) { }


  buscarRefaccion(refaccion){
    return this.http.post<myData>('/api/refacciones/read.php',{refaccion})
  }

}
