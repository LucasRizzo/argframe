package semantics;

import java.io.*;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.util.*;
import javaDungAF.DungAF;

/**
 * Servlet implementation class HelloServlet
 */
@WebServlet("/HelloServlet")
//Extend HttpServlet class
public class HelloServlet extends HttpServlet {

private String message;

public void init() throws ServletException
{
   // Do required initialization
   message = "Hello World";
}

public void doGet(HttpServletRequest request,
                 HttpServletResponse response)
         throws ServletException, IOException
{
   // Set response content type
   response.setContentType("text/html");
   PrintWriter out = response.getWriter();
   
   DungAF af = new DungAF(Arrays.asList(new String[]{"aba","e"}, new String[]{"f","a"}, new String[]{"a","b"}, new String[]{"b","c"}, new String[]{"c","d"}, new String[]{"b","d"}));
   HashSet<HashSet<String>> preferredExts = af.getPreferredExts();
   for (HashSet<String> nextSet : preferredExts) {
	   out.println("<h1>" + nextSet + "</h1><br>");
   }

   // Actual logic goes here.
   
   out.println("<h1>" + message + "</h1>");
}

public void destroy()
{
   // do nothing.
}
}