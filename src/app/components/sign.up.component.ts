/*
 this component is for signing up to use the site.
 */

//import needed modules for the sign-up component
import {Component, OnInit, ViewChild,} from "@angular/core";
import {Observable} from "rxjs/Observable"
import {Router} from "@angular/router";
import {Status} from "../classes/status";
import {SignUpService} from "../services/sign.up.service";
import {SignUp} from "../classes/sign.up";
import {setTimeout} from "timers";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";

//declare $ for good old jquery
declare let $: any;

// set the template url and the selector for the ng powered html tag
@Component({
	templateUrl: "./templates/sign-up.html",
	selector: "sign-up"
})
export class SignUpComponent{
}
