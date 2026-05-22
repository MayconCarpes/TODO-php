"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { LayoutDashboard, CheckSquare, LogOut, Menu, X } from "lucide-react";
import { useState } from "react";

export function Sidebar() {
  const pathname = usePathname();
  const [isOpen, setIsOpen] = useState(false);

  const links = [
    { href: "/dashboard", label: "Dashboard", icon: LayoutDashboard },
    { href: "/dashboard/tarefa/nova", label: "Nova Tarefa", icon: CheckSquare },
  ];

  return (
    <>
      <button 
        className="md:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-md shadow-md"
        onClick={() => setIsOpen(!isOpen)}
      >
        {isOpen ? <X size={24} /> : <Menu size={24} />}
      </button>

      <div className={`
        fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-slate-200 transform transition-transform duration-300 ease-in-out flex flex-col
        ${isOpen ? "translate-x-0" : "-translate-x-full md:translate-x-0"}
      `}>
        <div className="p-6">
          <h1 className="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
            <CheckSquare className="text-blue-600" />
            TaskFlow
          </h1>
        </div>

        <nav className="flex-1 px-4 space-y-2 mt-4">
          {links.map((link) => {
            const isActive = pathname === link.href;
            const Icon = link.icon;
            
            return (
              <Link 
                key={link.href} 
                href={link.href}
                className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 ${
                  isActive 
                    ? "bg-blue-50 text-blue-700 font-medium" 
                    : "text-slate-600 hover:bg-slate-50 hover:text-slate-900"
                }`}
                onClick={() => setIsOpen(false)}
              >
                <Icon size={20} className={isActive ? "text-blue-600" : "text-slate-400"} />
                {link.label}
              </Link>
            );
          })}
        </nav>

        <div className="p-4 border-t border-slate-200">
          <Link 
            href="/login"
            className="flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 transition-all duration-200"
          >
            <LogOut size={20} />
            <span className="font-medium">Sair</span>
          </Link>
        </div>
      </div>

      {isOpen && (
        <div 
          className="fixed inset-0 bg-slate-900/50 z-30 md:hidden"
          onClick={() => setIsOpen(false)}
        />
      )}
    </>
  );
}
