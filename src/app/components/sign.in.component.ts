//this component controls the sign-in modal when "sign-in" is clicked

import {Component, ViewChild, EventEmitter, Output,OnInit} from "@angular/core";



import {Router} from "@angular/router";
import {Observable} from "rxjs/Observable"
import {Status} from "../classes/status";
import {SignInService} from "../services/sign.in.service";
import {SignIn} from "../classes/sign.in";
import {CookieService} from "ng2-cookies";
import {JwtHelperService} from "@auth0/angular-jwt";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";

declare let $: any;

@Component({
    templateUrl: "./templates/signin-modal.html",
    selector: "sign-in"
})

export class SignInComponent implements OnInit{
	//@ViewChild("signInForm") signInForm: any;
	authObj: any = {};
	url: string;
	signInForm: FormGroup;




	//signIn: SignIn = new SignIn(null, null);
	status: Status = null;
	//cookie: any = {};
	constructor(private jwtHelperService: JwtHelperService, private signInService: SignInService, private router: Router,
					private cookieService : CookieService, private formBuilder: FormBuilder) {
	}

	ngOnInit() : void {
		this.signInForm = this.formBuilder.group({
			userEmail:["", [Validators.maxLength(128), Validators.required]],
			password: ["", [Validators.maxLength(48), Validators.required]]
		});
	}



	createSignIn(): void {
		let signin : SignIn =new SignIn (this.signInForm.value.userEmail, this.signInForm.value.password);
		console.log(signin);
		localStorage.removeItem("jwt-token");
		this.signInService.postSignIn(signin).subscribe(status => {
			this.status = status;

			if(status.status === 200) {

				//location.reload(true);
				this.signInForm.reset();
				$("#signInModal").modal('hide');
			} else {
				console.log("failed login")
			}
			this.authObj = this.jwtHelperService.decodeToken(localStorage.getItem("jwt-token"));
			this.url = "profile/" + this.authObj.auth["userId"];
			this.router.navigate([this.url]);
		});
	}

	getJwtProfileId() : any {
		this.authObj = this.jwtHelperService.decodeToken(localStorage.getItem("jwt-token"));
	}
}