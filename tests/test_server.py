import socket

def start_test_server():
    host = "0.0.0.0"  # Escucha en todas las interfaces
    port = 1234

    # Crear un socket
    server_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    server_socket.bind((host, port))
    server_socket.listen(1)

    print(f"Servidor de pruebas escuchando en {host}:{port}...")

    while True:
        conn, addr = server_socket.accept()
        print(f"Conexión establecida con {addr}")

        # Recibir datos del cliente
        data = conn.recv(1024).decode()
        print(f"Mensaje recibido: {data}")

        # Simular una respuesta
        conn.send("1\n".encode())  # Simula la respuesta con un ID de empleado

        # Cerrar la conexión
        conn.close()

if __name__ == "__main__":
    start_test_server()