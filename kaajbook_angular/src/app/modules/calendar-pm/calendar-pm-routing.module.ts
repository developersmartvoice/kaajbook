import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { CalendarListComponent } from './pages/calendar-list/calendar-list.component';

const routes: Routes = [
    {
        path: '',
        component: CalendarListComponent
    }
];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})

export class CalendarPmRoutingModule { }
