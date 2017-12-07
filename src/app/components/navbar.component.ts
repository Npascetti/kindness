import {Component, OnChanges, OnInit} from "@angular/core";
import {JwtHelperService} from "@auth0/angular-jwt";
import {User} from "../classes/user";
import {SignInService} from "../services/sign.in.service";
import {CookieService} from "ng2-cookies";
import {Router} from "@angular/router";
import {Status} from "../classes/status";
//enable Jquery $ alias
declare const $: any;

@Component({
	templateUrl: "./templates/navbar.html",
	selector: "navbar"
})

export class NavbarComponent implements OnChanges, OnInit{
	user:User = new User(null, null, null, null, null, null, null, null, null) ;

	authObj: any = {};

	constructor (
		private jwtHelperService: JwtHelperService, private signInService: SignInService, private cookieService: CookieService, private router: Router
	){}

	ngOnInit() : void {
		this.getJwtProfileId();
	}

	ngOnChanges() : void {
			this.getJwtProfileId();
	}

	getJwtProfileId() : any {
		this.authObj = this.jwtHelperService.decodeToken(localStorage.getItem("jwt-token"));
	}

	// signOut() : void {
	// 		console.log("fuck your mom");
	// 		this.signInService.signOut().subscribe(status => {
	// 			this.status = status;
	// 			if(status.status === 200) {
	// 				this.cookieService.deleteAll();
	// 				localStorage.clear();
	// 				this.router.navigate([""]);
	// 				location.reload();
	// 				console.log("goodbye");
	// 			}
	// 		});
	// }
}
