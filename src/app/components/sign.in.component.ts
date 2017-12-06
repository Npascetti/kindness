//this component controls the sign-in modal when "sign-in" is clicked
import {Component, ViewChild, EventEmitter, Output} from "@angular/core";



import {Router} from "@angular/router";
import {Observable} from "rxjs/Observable"
import {Status} from "../classes/status";
import {SignInService} from "../services/sign.in.service";
import {SignIn} from "../classes/sign.in";
import {CookieService} from "ng2-cookies";
import {setTimeout} from "timers";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";

@Component({
    templateUrl: "./templates/signin-modal.html",
    selector: "sign-in"
})

export class SignInComponent {
}