import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Status} from "../classes/status";
import {Level} from "../classes/level";
import {Observable} from "rxjs/Observable";

@Injectable ()
export class LevelService {

	constructor(protected http : HttpClient ) {

	}

	//define the API endpoint
	private levelUrl = "/api/level/";


	//call the level API and create a new level
	createLevel(level : Level) : Observable<Status> {
		return (this.http.post<Status>(this.levelUrl, level));
	}

	//grabs a  level based on its primary key
	getLevel(levelId : string) : Observable <Level> {
		return (this.http.get<Level>(this.levelUrl+ levelId))
	}


}