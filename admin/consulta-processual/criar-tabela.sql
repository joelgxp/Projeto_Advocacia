-- Tabela para armazenar histórico de consultas processuais
CREATE TABLE IF NOT EXISTS consultas_processuais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_processo VARCHAR(50) NOT NULL,
    dados_consulta TEXT,
    usuario VARCHAR(100),
    data_consulta DATETIME NOT NULL,
    INDEX idx_numero_processo (numero_processo),
    INDEX idx_data_consulta (data_consulta)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

