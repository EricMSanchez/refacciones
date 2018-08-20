import { Component, OnInit } from '@angular/core';
import { RefaccionesService } from '../refacciones.service'
import {DomSanitizer} from '@angular/platform-browser';

@Component({
  selector: 'app-buscador',
  templateUrl: './buscador.component.html',
  styleUrls: ['./buscador.component.css']
})
export class BuscadorComponent implements OnInit {

  busqueda = null;
  constructor(private refacciones:RefaccionesService,private domsanitizer:DomSanitizer) { }

  results = null;
  	message = null;
  ngOnInit() {
  }

  transformTrustUrl(url){
    //this..
  }
  
  buscarRefaccion(){
    this.refacciones.buscarRefaccion(this.busqueda).subscribe(data => {
      if(data.success){
        this.results = data.message;
        this.message = null;
      }else{
        this.results =null;
        this.message = 'No se encontraron resultados';
      }
    })
  }

}
