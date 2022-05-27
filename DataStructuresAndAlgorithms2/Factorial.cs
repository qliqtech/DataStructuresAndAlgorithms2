using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace DataStructuresAndAlgorithms2
{
    public class Factorial
    {


        //factorial of 5
        // 1 * 2 * 3 * 4 * 5
        // start from 0 and keep incrementing till 5
        //with each increment multiply by prvious result

        public int factorialiterative(int n) {

            int fact = 1;

            for (int i = 1; i <= n; i++) {

                fact = fact * i;
               // i++;
            
            
            }
            return fact;
        }


        public int factorialrecursive(int n) {


            if (n == 0)
                return 0;

            return factorialrecursive(n - 1) * n;
        
        }

    }
}
