import http from "../http-common.js";
class PostService{
    post(path,data){
        return http.post(`/${path}`,data);
    }
}

export default new PostService();