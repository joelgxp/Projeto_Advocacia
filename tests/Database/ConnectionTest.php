<?php

namespace Advocacia\Tests\Database;

use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
    public function testConnectionClassExists()
    {
        $this->assertTrue(class_exists('Advocacia\Database\Connection'));
    }
    
    public function testConnectionNamespaceIsCorrect()
    {
        $reflection = new \ReflectionClass('Advocacia\Database\Connection');
        $this->assertEquals('Advocacia\Database', $reflection->getNamespaceName());
    }
    
    public function testConnectionHasRequiredMethods()
    {
        $reflection = new \ReflectionClass('Advocacia\Database\Connection');
        $methods = $reflection->getMethods();
        $methodNames = array_map(function($method) {
            return $method->getName();
        }, $methods);
        
        $requiredMethods = [
            'getInstance',
            'getPdo',
            'query',
            'fetchAll',
            'fetch',
            'lastInsertId',
            'beginTransaction',
            'commit',
            'rollback'
        ];
        
        foreach ($requiredMethods as $method) {
            $this->assertContains($method, $methodNames, "Método {$method} não encontrado");
        }
    }
    
    public function testConnectionIsSingleton()
    {
        $reflection = new \ReflectionClass('Advocacia\Database\Connection');
        $constructor = $reflection->getConstructor();
        
        // Verifica se o construtor é privado (padrão Singleton)
        $this->assertTrue($constructor->isPrivate(), 'Construtor deve ser privado para Singleton');
        
        // Verifica se existe método getInstance estático
        $getInstanceMethod = $reflection->getMethod('getInstance');
        $this->assertTrue($getInstanceMethod->isStatic(), 'getInstance deve ser estático');
        $this->assertTrue($getInstanceMethod->isPublic(), 'getInstance deve ser público');
    }
    
    public function testConnectionFileStructure()
    {
        $filePath = __DIR__ . '/../../src/Database/Connection.php';
        $this->assertFileExists($filePath, 'Arquivo Connection.php deve existir');
        
        $fileContent = file_get_contents($filePath);
        $this->assertStringContainsString('namespace Advocacia\\Database', $fileContent, 'Namespace deve ser Advocacia\Database');
        $this->assertStringContainsString('class Connection', $fileContent, 'Classe deve se chamar Connection');
    }
}

