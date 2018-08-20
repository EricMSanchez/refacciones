import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap'
import { RouterModule } from '@angular/router';
import { AppComponent } from './app.component';
import { BuscadorComponent } from './buscador/buscador.component';
import { FormsModule } from '../../node_modules/@angular/forms';
import { RefaccionesService } from './refacciones.service';
import { HttpClientModule } from '@angular/common/http';
@NgModule({
  declarations: [
    AppComponent,
    BuscadorComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpClientModule,
    NgbModule,
    RouterModule.forRoot([
      {
        path: '',
        component: BuscadorComponent
      }
    ])
  ],
  providers: [RefaccionesService],
  bootstrap: [AppComponent]
})
export class AppModule { }
