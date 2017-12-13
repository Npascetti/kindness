import {RouterModule, Routes, CanActivate} from "@angular/router";
import {AuthGuardService as AuthGuard} from './services/auth.guard.service';

//import all needed components
import {HomeComponent} from "./components/home.component";
import {NavbarComponent} from "./components/navbar.component";
import {HubPanelComponent} from "./components/hub.panel.component";
import {ContentPanelComponent} from "./components/content.panel.component";
import {FooterComponent} from "./components/footer.component";
import {SignInComponent} from "./components/sign.in.component";
import {SignUpComponent} from "./components/sign.up.component";
import {ProfilePageComponent} from "./components/profile.page.component";
import {ProfilePanelComponent} from "./components/profile.panel.component";
import {CreateHubModalComponent} from "./components/create.hub.modal.component";
import {EditProfileComponent} from "./components/edit.profile.component";
import {AdminpanelComponent} from "./components/adminpanel.component";
import {DeleteProfileModalComponent} from "./components/delete.profile.modal.component";
import {EditHubModalComponent} from "./components/edit.hub.modal.component";
import {ImageComponent} from "./components/image.component";
import {FileSelectDirective} from "ng2-file-upload";


// import all needed Services
import {CookieService} from "ng2-cookies";
import {JwtHelperService} from "@auth0/angular-jwt";
import {SessionService} from "./services/session.service";
import {SignUpService} from "./services/sign.up.service";
import {SignInService} from "./services/sign.in.service";
import {HubService} from "./services/hub.service";
import {UserService} from"./services/user.service";
import {AuthService} from "./services/auth.service";
import {AuthGuardService} from "./services/auth.guard.service";


//import all needed Interceptors
import {APP_BASE_HREF} from "@angular/common";
import {HTTP_INTERCEPTORS} from "@angular/common/http";
import {DeepDiveInterceptor} from "./services/deep.dive.interceptor";

//an array of the components that will be passed off to the module
export const allAppComponents = [HomeComponent, NavbarComponent, HubPanelComponent, ContentPanelComponent, FooterComponent,
	SignInComponent, SignUpComponent, ProfilePageComponent, ProfilePanelComponent, CreateHubModalComponent,
	EditProfileComponent, AdminpanelComponent, DeleteProfileModalComponent, EditHubModalComponent, ImageComponent, FileSelectDirective];
/*we need our own components */

//an array of routes that will be passed of to the module
export const routes: Routes = [
	{path: "", component: HomeComponent},
	// {path: "profile", component: ProfilePageComponent},
	//{path: "profile/:id", component: ProfilePanelComponent},
    {path: 'profile/:id', component: ProfilePageComponent, canActivate: [AuthGuard]},
    {path: '**', redirectTo: '' }

];

// an array of services
const services: any[] = [CookieService, JwtHelperService, SessionService, SignUpService, SignInService,
	HubService, UserService, AuthService, AuthGuardService];

// an array of misc providers
const providers: any[] = [
	{provide: APP_BASE_HREF, useValue: window["_base_href"]},
	{provide: HTTP_INTERCEPTORS, useClass: DeepDiveInterceptor, multi: true},

];

export const appRoutingProviders: any[] = [providers, services];

export const routing = RouterModule.forRoot(routes);