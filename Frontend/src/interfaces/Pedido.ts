export interface Pedido {
    id: number
    user_id: number
    destination: string
    departure_date: Date
    return_date: Date
    status_id: number
    status: Status
    user: User
  }
  
  export interface Status {
    id: number
    name: string
  }
  
  export interface User {
    id: number
    name: string
    email: string
  }
  
  export interface BuscarPedidosParams {
    page?: number
    departure_date?: string
    return_date?: string
    destination?: string
    status_id?: string
  }

  export interface PedidoForm {
    destination: string
    departure_date: ''
    return_date: ''
  }
  