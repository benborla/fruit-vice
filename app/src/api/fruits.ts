import http from "@/api/http-common";

class FruitsApi {
  all(size: number, direction: string, orderBy: string, search: string, page: number): Promise<any> {
    return http.get(`/?search=${search}&size=${size}&direction=${direction}&order_by=${orderBy}&page=${page}`);
  }

  new(data: {}): Promise<any> {
    return http.put(`/fruit`, data);
  }

  get(id: number): Promise<any> {
    return http.get(`/fruit/${id}`);
  }

  update(id: number, data: {}): Promise<any> {
    return http.patch(`/fruit/${id}`, data);
  }

  delete(id: number): Promise<any> {
    return http.delete(`/fruit/${id}`);
  }

  favorites(): Promise<any> {
    return http.get('/favorites');
  }

  favorite(fruitId: number): Promise<any> {
    return http.post(`/favorite/${fruitId}`);
  }

  removeFavorite(fruitId: number) {
    return http.delete(`/favorite/${fruitId}`);
  }
}

export default new FruitsApi();
