type Nullable<T> = T | null;

export default interface Fruit {
  id: Nullable<number>,
  name: string,
  genus: string,
  family: string,
  fruitOrder: string,
  carbohydrates: number,
  protein: number,
  fat: number,
  sugar: number,
  calories: number,
  createdAt: {
    date: string
  },
  updatedAt: {
    date: string
  },
  source: string,
  favorite: Nullable<object>
}
